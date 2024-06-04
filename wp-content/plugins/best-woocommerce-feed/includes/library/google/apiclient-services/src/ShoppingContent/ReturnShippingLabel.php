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

class ReturnShippingLabel extends \RexFeed\Google\Model
{
    /**
     * @var string
     */
    public $carrier;
    /**
     * @var string
     */
    public $labelUri;
    /**
     * @var string
     */
    public $trackingId;
    /**
     * @param string
     */
    public function setCarrier($carrier)
    {
        $this->carrier = $carrier;
    }
    /**
     * @return string
     */
    public function getCarrier()
    {
        return $this->carrier;
    }
    /**
     * @param string
     */
    public function setLabelUri($labelUri)
    {
        $this->labelUri = $labelUri;
    }
    /**
     * @return string
     */
    public function getLabelUri()
    {
        return $this->labelUri;
    }
    /**
     * @param string
     */
    public function setTrackingId($trackingId)
    {
        $this->trackingId = $trackingId;
    }
    /**
     * @return string
     */
    public function getTrackingId()
    {
        return $this->trackingId;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(ReturnShippingLabel::class, 'RexFeed\\Google_Service_ShoppingContent_ReturnShippingLabel');
