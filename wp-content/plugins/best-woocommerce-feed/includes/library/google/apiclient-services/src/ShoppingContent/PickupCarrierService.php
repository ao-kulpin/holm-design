<?php

/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */
namespace RexFeed\Google\Service\ShoppingContent;

class PickupCarrierService extends \RexFeed\Google\Model
{
    /**
     * @var string
     */
    public $carrierName;
    /**
     * @var string
     */
    public $serviceName;
    /**
     * @param string
     */
    public function setCarrierName($carrierName)
    {
        $this->carrierName = $carrierName;
    }
    /**
     * @return string
     */
    public function getCarrierName()
    {
        return $this->carrierName;
    }
    /**
     * @param string
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
    }
    /**
     * @return string
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(PickupCarrierService::class, 'RexFeed\\Google_Service_ShoppingContent_PickupCarrierService');
