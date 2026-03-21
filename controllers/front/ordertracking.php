<?php
   class AgCorreiosOrderTrackingModuleFrontController extends ModuleFrontController
   {
       public function initContent()
       {
            parent::initContent();
            
            // Your order tracking logic here
            // For example, get the order ID from the request
            $orderId = Tools::getValue('id_order');
            
            // Fetch order details
            $order = new Order($orderId);

            $sql = "SELECT 
                at.tracking_code, 
                ate.*
            FROM 
                "._DB_PREFIX_."agcorreios_tracking at
            LEFT JOIN 
                "._DB_PREFIX_."agcorreios_tracking_events ate ON at.id_agcorreios_tracking = ate.id_agcorreios_tracking
            WHERE 
                at.id_order = ".$orderId."
            ORDER BY 
                ate.date_add DESC";

            $trackingData = Db::getInstance()->executeS($sql);

            // Separate tracking code and events
            $trackingInfo = !empty($trackingData) ? $trackingData[0]['tracking_code'] : null;
            $trackingEvents = $trackingData; // All events are already fetched

            // Assign variables to template
            $this->context->smarty->assign([
                'order' => $order,
                'trackingInfo' => $trackingInfo,
                'trackingEvents' => $trackingEvents
            ]);
            
            // Render the template
            $this->setTemplate('module:agcorreios/views/templates/front/rastreio.tpl');
       }
   }
