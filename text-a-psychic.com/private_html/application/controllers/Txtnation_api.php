<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'libraries/REST_Controller.php');

use ElephantIO\Client as ElephantClient;
use ElephantIO\Engine\SocketIO\Version1X as SocketIO;

class Txtnation_api extends REST_Controller
{

    /**
     * Allowed IP server array
     *
     */
    private $_allowedServers = array('127.0.0.1', '67.23.27.65','72.32.41.114','72.32.41.115','74.54.223.228','74.54.223.230','95.138.180.235','174.143.237.218','174.143.239.166','166.78.143.213','162.13.59.148','162.13.52.70','162.13.104.239','37.153.99.112');   

    /**
     * And all other info
     *
     */
    private $_ekey = '1a989ce5a9b504b267a810be41c8d114';
    private $_chargeAmt = '1.5';
    private $_chargeCurrency = 'gbp';

    private $_companyName = 'psychiccontact';
    private $_txtnationGateway = 'http://client.txtnation.com/gateway.php';

    /**
     * Responder API call by TxtNation gateway on message receive
     *
     */
    public function receive_message_post()
    {   
        /**
         * Let's respond to TxtNation
         * Reponse for this does not follow json instead TXTNation gateway specs
         */
        if (in_array($_SERVER['REMOTE_ADDR'], $this->_allowedServers)) {
            $number = $this->post('number');
            $message = $this->post('message');
            $network = $this->post('network');
            $id = $this->post('id');

            if (!$id) {
                echo 'Invalid ID';
                return;
            } else if (!$number) {
                echo 'Invalid number';
                return;
            } else if (!$message) {
                echo 'Invalid message';
                return;
            } else if (!$network) {
                echo 'Invalid network';
                return;
            }

            $responseText = urlencode('We have successfully received your message. A psychic will reply to in a short while.');

            $strPostReq = 'reply=1';
            $strPostReq .= '&id=' . $id;
            $strPostReq .= '&number=' . $number;
            $strPostReq .= '&network=' . $network;
            $strPostReq .= '&message=' . $responseText;
            $strPostReq .= '&value=' . $this->_chargeAmt;
            $strPostReq .= '&currency=' . $this->_chargeCurrency;
            $strPostReq .= '&cc=' . $this->_companyName;
            $strPostReq .= '&title=';
            $strPostReq .= '&ekey=' . $this->_ekey;

            $postData = $this->post();
            $postData['request_url'] = $strPostReq;
            $postData['txtnation_msg_id'] = $postData['id'];
            $socketData = $postData;
            unset($postData['id']);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->_txtnationGateway);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "$strPostReq");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            $strBuffer = curl_exec($ch);
            curl_close($ch);

            // $this->email->from('ellentxtapsy@gmail.com', 'Ellen');
            $this->email->from('testing@text-a-psychic.com', 'Txtapsy');
            $this->email->to('dianahamster67@gmail.com, ellen051394@gmail.com, jlynndfs@yahoo.com', 'ericvp2016@gmail.com');

            if(strstr($strBuffer, 'SUCCESS')){
                $inboundMsg = new Inbound_message_model($postData);
                $inboundMsg->save();

                // Trigger websockets
                SocketIO_helper::sendEvent('message_recieved', $socketData);

                $this->email->subject('TXTNation Message Recieved');
                $this->email->message("A TXTNation message has beed recieved.<br>Response to TXTNation from our server is '$strBuffer'. Excerpt as follows: <br><br>" . 
                    $this->pretiffyJSON($postData));  

                $this->email->send();

                echo 'OK';
            } else {
                $this->email->subject('TXTNation Message Auto-reply Failed');
                $this->email->message("A TXTNation message has beed recieved, but failed to auto-reply.<br>Response to TXTNation from our server is '$strBuffer'. Excerpt as follows: <br><br>" . 
                    $this->pretiffyJSON($postData));  

                $this->email->send();
                
                echo $strBuffer;
            }

        } else {
            echo 'Server now allowed to call this API';
        }
    }

    /**
     * Delivery report API
     *
     */
    public function delivery_report_post()
    {

    }

    public function send_message_post()
    {
        $success = true;
        $errors = [];
        $data = [];

        $fromMessageId = $this->post('ref_message_id');
        $senderId = $this->post('sender_id');
        $message = $this->post('message');

        if (!$fromMessageId) {
            $errors[] = 'You must include an inbound message ID';
        }
        if (!$message) {
            $errors[] = 'You must include a message to send';
        }
        if (!$senderId) {
            $errors[] = 'You must include a Psychic ID';
        }

        $sender = $this->Psychic_model->first([
            'where' => [
                'id' => $senderId
            ]
        ]);
        if (!$sender) {
            $errors[] = 'Psychic does not exist';
        }

        $inboundMsg = $this->Inbound_message_model->first([
            'where' => [
                'id' => $fromMessageId
            ]
        ]);
        if (!$inboundMsg) {
            $errors[] = 'Inbound message does not exist';
        } else if ($inboundMsg->responded_by && $senderId != $inboundMsg->responded_by) {
            $errors[] = 'This is being read by a different psychic';
        } else if ($inboundMsg->status != Inbound_message_model::STATUS_PENDING) {
            $errors[] = 'Message is not yet marked as read';
        }

        if (count($errors)) {
            $success = false;
            $inboundMsg->status = Inbound_message_model::STATUS_AVAILABLE;
            $inboundMsg->responded_by = 0;
            if ($inboundMsg->save()) {
                $data = $inboundMsg->to('array');
                
                SocketIO_helper::sendEvent('message_declined', $data);
            }

            return $this->response(compact('success', 'errors'));
        }

        $sender = $sender->displayName;
        $number = $inboundMsg->number;

        $req = 'reply=0';
        $req .= '&number=' . $number;
        $req .= '&network=INTERNATIONAL';
        $req .= '&message=' . urlencode($message);
        $req .= '&value=0';
        $req .= '&currency=' . $this->_chargeCurrency;
        $req .= '&cc=' . $this->_companyName;
        $req .= '&title=' . $sender;
        $req .= '&ekey=' . $this->_ekey;

        $postData = $this->post();
        $postData['request_url'] = $req;
        $outboundMsg = new Outbound_message_model($postData);
        $outboundMsg->save();
        $messageId = $outboundMsg->id;
        $req .= '&id=' . $messageId;

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $this->_txtnationGateway,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $req,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10
        ));
        $result = curl_exec($ch);
        curl_close($ch);

        // $result = 'SUCCESS';
        if(strstr($result, 'SUCCESS')){
            $inboundMsg->status = Inbound_message_model::STATUS_RESOLVED;
            if ($inboundMsg->save()) {
                $data = $outboundMsg->to('array');
                SocketIO_helper::sendEvent('message_resolved', $data);
            } else {
                $errors[] = "Unable to save";
            }
        } else {
            $outboundMsg->delete();
            $inboundMsg->status = Inbound_message_model::STATUS_AVAILABLE;
            $inboundMsg->responded_by = 0;
            if ($inboundMsg->save()) {
                $data = $inboundMsg->to('array');
                
                SocketIO_helper::sendEvent('message_declined', $data);
            }
            $errors[] = "Recipient $number: $result";
        }

        if (count($errors)) {
            $success = false;
        }

        return $this->response(compact('success', 'errors', 'data'));
    }

    private function pretiffyJSON ($json)
    {
        $json = str_replace(array("\\r","\\n","\\t"), "", json_encode($json, JSON_PRETTY_PRINT));
        $json = preg_replace('#(?<!\\\\)(\\$|\\\\)#', "", $json);

        $string = '<pre>';
        $string .= $json;
        $string .= '</pre><hr>';

        return $string;
    }
}
