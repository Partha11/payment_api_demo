<?php

namespace App\Http\Controllers\Payments;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use Exception;

class PaymentController extends Controller {

    public function create() {

        try {

            $body = file_get_contents('php://input');
            $data = json_decode($body, true);

            if (!isset($data['amount'])) {

                http_response_code(422);

                echo json_encode([
                    "message" => "Missing information from the request body."
                ]);

            } else {

                $amount = $data['amount'];
                $invoice_id = 0;

                if (isset($data['invoice_id']) && !is_null($data['invoice_id'])) {
                    
                    $invoice_id = intval($data['invoice_id']);
                }

                if (!$this->filesystem->fileExists('invoices.json')) {

                    http_response_code(404);

                    echo json_encode([
                        "message" => "Invoices database does not exist. Please create an invoice first."
                    ]);

                } elseif ($invoice_id == null) {

                    $this->createPayment([
                        "amount" => $amount,
                        "invoice_id" => 0,
                        "payload" => $data
                    ]);

                } else {

                    $invoices = json_decode(
                        $this->filesystem
                            ->read('invoices.json'),
                        true
                    );

                    for ($i = 0; $i < count($invoices); $i++) {

                        $invoice = $invoices[$i];

                        if ($invoice['invoice']['id'] == $invoice_id) {

                            if ($invoice['invoice']['due_amount'] == 0) {

                                $this->createPayment([
                                    "amount" => $amount,
                                    "invoice_id" => 0,
                                    "payload" => $data
                                ]);

                                http_response_code(422);

                                echo json_encode([
                                    "message" => "This invoice has already been paid."
                                ]);

                            } else {

                                $invoice['invoice']['due_amount'] = max(0, $invoice['invoice']['due_amount'] - $amount);
                                $invoices[$i] = $invoice;

                                $this->filesystem
                                    ->write('invoices.json', json_encode($invoices));

                                $this->createPayment([
                                    "amount" => $amount,
                                    "invoice_id" => $invoice_id,
                                    "payload" => $data
                                ]);

                                http_response_code(204);
                                echo json_encode([]);
                            }

                            return;
                        }
                    }

                    throw new NotFoundException();
                }
            }

        } catch(NotFoundException $ex) {

            http_response_code($ex->getCode());

            echo json_encode([
                "message" => "Invoice not found. Please check your invoice id and try again."
            ]);

        } catch (Exception $ex) {

            $this->logger->error($ex->getMessage());
            http_response_code(500);

            echo json_encode([
                "message" => "Something went wrong. Please try again."
            ]);
        }
    }

    private function createPayment($payment) {

        $payments = [];

        if ($this->filesystem->fileExists('payments.json')) {

            $payments = json_decode(
                $this->filesystem
                    ->read('payments.json'),
                true
            );
        }

        $payments[] = $payment;

        $this->filesystem
            ->write('payments.json', json_encode($payments));
    }
}
