<?php

namespace App\Http\Controllers\Invoices;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use Exception;

class InvoiceController extends Controller {

    public function index() {

        try {

            if ($this->filesystem->fileExists('invoices.json')) {

                $data = json_decode(
                    $this->filesystem
                        ->read('invoices.json'),
                    true
                );
                $invoices = [];

                if ($data == null || empty($data)) {

                    throw new NotFoundException();
                }

                foreach ($data as $d) {

                    if ($d['invoice']['due_amount'] != 0) {

                        $invoices[] = $d["invoice"];
                    }
                }

                echo json_encode([
                    "invoices" => $invoices
                ]);

            } else {

                echo json_encode([
                    "message" => "No invoices found. Please create an invoice first."
                ]);
            }

        } catch (NotFoundException $exception) {

            http_response_code($exception->getCode());

            echo json_encode([
                "message" => "Invoice database not found. Please create an invoice first."
            ]);

        } catch (Exception $ex) {

            $this->logger->error($ex->getMessage());

            echo json_encode([
                "message" => "Something went wrong. Please try again."
            ]);
        }
    }

    public function find($id = null) {

        try {

            if (!isset($id) || $id <= 0) {

                echo json_encode([
                    "message" => "Something went wrong. Please try again."
                ]);
            } elseif ($this->filesystem->fileExists('invoices.json')) {

                $invoices = json_decode($this->filesystem->read('invoices.json'), true);

                foreach ($invoices as $invoice) {

                    if ($invoice['invoice']['id'] == $id) {

                        echo json_encode([
                            "invoice" => $invoice['invoice']
                        ]);
                        break;
                    }
                }

            } else {

                echo json_encode([
                    "message" => "No invoices found. Please create an invoice first."
                ]);
            }

        } catch (Exception $ex) {

            $this->logger->error($ex->getMessage());

            echo json_encode([
                "message" => "Something went wrong. Please try again."
            ]);
        }
    }

    public function update() {

        $data = file_get_contents('php://input');

        echo json_encode($data);
    }

    public function create() {

        try {

            $amount = rand(1000, 10000);

            $body = [
                "id" => 1,
                "invoice" => [
                    "id" => rand(100000, 999999),
                    "title" => "TWC-" . rand(100000, 999999),
                    "amount" => $amount,
                    "due_amount" => $amount,
                    "created_at" => date('Y-m-d H:i:s')
                ]
            ];

            $invoices = [];

            if ($this->filesystem->fileExists('invoices.json')) {

                $invoices = json_decode($this->filesystem->read('invoices.json'), true);
            }

            $invoices[] = $body;
            $this->filesystem->write('invoices.json', json_encode($invoices));

        } catch (Exception $ex) {

            $this->logger->error($ex->getMessage());
        }
    }
}
