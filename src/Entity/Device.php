<?php

namespace App\Entity;

class Device extends Serialize
{
    private $id;

    private $customer;

    private $schoolLocation;

    private $productnumber;

    private $manufacturer;

    private $model;

    private $motherboardCode;

    private $motherboardValue;

    private $panelCode;

    private $panelValue;

    private $adapter;

    private $keyboard;

    private $panelAssemblyCode;

    private $panelAssemblyValue;

    private $battery;

    private $ssdCode;

    private $ssdValue;

    private $hddCode;

    private $hddValue;

    private $topcover;

    private $displayBezel;

    private $displayBackplate;

    private $touchpad;

    private $bottomCover;

    private $memoryCode;

    private $memoryValue;

    private $powerbutton;

    private $wifiAdapter;

    private $wifiAntenna;

    private $lcdCable;

    private $hinges;

    private $webcam;

    private $speakers;

    private $dcIn;

    private $cablekit;

    private $dvddrive;

    private $usbAudioboard;

    private $systemIoBoard;

    private $fanHeatsink;

    private $bottomDoor;

    private $misc;

    private $picture;

    private $hostname;

    private $label;

    private $serialnumber;

    private $mac1;

    private $mac2;

    private $productCode;

    private $servicemodelId;

    private $servicemodelOrder;

    private $endoflife;

    private $warranty;

    private $intOrderId;

    private $extOrderTime;

    private $extDeliveryTime;

    private $deleted;

    private $freefieldtag01;

    private $freefieldtag02;

    private $freefieldtag03;

    private $freefieldtag04;

    private $freefieldtag05;

	private $schoolId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getSchoolLocation(): ?InstitutionLocation
    {
        return $this->schoolLocation;
    }

    public function setSchoolLocation(?InstitutionLocation $schoolLocation): self
    {
        $this->schoolLocation = $schoolLocation;

        return $this;
    }

    public function getProductnumber(): ?string
    {
        return $this->productnumber;
    }

    public function setProductnumber(?string $productnumber): self
    {
        $this->productnumber = $productnumber;

        return $this;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?string $manufacturer): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getMotherboardCode(): ?string
    {
        return $this->motherboardCode;
    }

    public function setMotherboardCode(?string $motherboardCode): self
    {
        $this->motherboardCode = $motherboardCode;

        return $this;
    }

    public function getMotherboardValue(): ?string
    {
        return $this->motherboardValue;
    }

    public function setMotherboardValue(?string $motherboardValue): self
    {
        $this->motherboardValue = $motherboardValue;

        return $this;
    }

    public function getPanelCode(): ?string
    {
        return $this->panelCode;
    }

    public function setPanelCode(?string $panelCode): self
    {
        $this->panelCode = $panelCode;

        return $this;
    }

    public function getPanelValue(): ?string
    {
        return $this->panelValue;
    }

    public function setPanelValue(?string $panelValue): self
    {
        $this->panelValue = $panelValue;

        return $this;
    }

    public function getAdapter(): ?string
    {
        return $this->adapter;
    }

    public function setAdapter(?string $adapter): self
    {
        $this->adapter = $adapter;

        return $this;
    }

    public function getKeyboard(): ?string
    {
        return $this->keyboard;
    }

    public function setKeyboard(?string $keyboard): self
    {
        $this->keyboard = $keyboard;

        return $this;
    }

    public function getPanelAssemblyCode(): ?string
    {
        return $this->panelAssemblyCode;
    }

    public function setPanelAssemblyCode(?string $panelAssemblyCode): self
    {
        $this->panelAssemblyCode = $panelAssemblyCode;

        return $this;
    }

    public function getPanelAssemblyValue(): ?string
    {
        return $this->panelAssemblyValue;
    }

    public function setPanelAssemblyValue(?string $panelAssemblyValue): self
    {
        $this->panelAssemblyValue = $panelAssemblyValue;

        return $this;
    }

    public function getBattery(): ?string
    {
        return $this->battery;
    }

    public function setBattery(?string $battery): self
    {
        $this->battery = $battery;

        return $this;
    }

    public function getSsdCode(): ?string
    {
        return $this->ssdCode;
    }

    public function setSsdCode(?string $ssdCode): self
    {
        $this->ssdCode = $ssdCode;

        return $this;
    }

    public function getSsdValue(): ?string
    {
        return $this->ssdValue;
    }

    public function setSsdValue(?string $ssdValue): self
    {
        $this->ssdValue = $ssdValue;

        return $this;
    }

    public function getHddCode(): ?string
    {
        return $this->hddCode;
    }

    public function setHddCode(?string $hddCode): self
    {
        $this->hddCode = $hddCode;

        return $this;
    }

    public function getHddValue(): ?string
    {
        return $this->hddValue;
    }

    public function setHddValue(?string $hddValue): self
    {
        $this->hddValue = $hddValue;

        return $this;
    }

    public function getTopcover(): ?string
    {
        return $this->topcover;
    }

    public function setTopcover(?string $topcover): self
    {
        $this->topcover = $topcover;

        return $this;
    }

    public function getDisplayBezel(): ?string
    {
        return $this->displayBezel;
    }

    public function setDisplayBezel(?string $displayBezel): self
    {
        $this->displayBezel = $displayBezel;

        return $this;
    }

    public function getDisplayBackplate(): ?string
    {
        return $this->displayBackplate;
    }

    public function setDisplayBackplate(?string $displayBackplate): self
    {
        $this->displayBackplate = $displayBackplate;

        return $this;
    }

    public function getTouchpad(): ?string
    {
        return $this->touchpad;
    }

    public function setTouchpad(?string $touchpad): self
    {
        $this->touchpad = $touchpad;

        return $this;
    }

    public function getBottomCover(): ?string
    {
        return $this->bottomCover;
    }

    public function setBottomCover(?string $bottomCover): self
    {
        $this->bottomCover = $bottomCover;

        return $this;
    }

    public function getMemoryCode(): ?string
    {
        return $this->memoryCode;
    }

    public function setMemoryCode(?string $memoryCode): self
    {
        $this->memoryCode = $memoryCode;

        return $this;
    }

    public function getMemoryValue(): ?string
    {
        return $this->memoryValue;
    }

    public function setMemoryValue(?string $memoryValue): self
    {
        $this->memoryValue = $memoryValue;

        return $this;
    }

    public function getPowerbutton(): ?string
    {
        return $this->powerbutton;
    }

    public function setPowerbutton(?string $powerbutton): self
    {
        $this->powerbutton = $powerbutton;

        return $this;
    }

    public function getWifiAdapter(): ?string
    {
        return $this->wifiAdapter;
    }

    public function setWifiAdapter(?string $wifiAdapter): self
    {
        $this->wifiAdapter = $wifiAdapter;

        return $this;
    }

    public function getWifiAntenna(): ?string
    {
        return $this->wifiAntenna;
    }

    public function setWifiAntenna(?string $wifiAntenna): self
    {
        $this->wifiAntenna = $wifiAntenna;

        return $this;
    }

    public function getLcdCable(): ?string
    {
        return $this->lcdCable;
    }

    public function setLcdCable(?string $lcdCable): self
    {
        $this->lcdCable = $lcdCable;

        return $this;
    }

    public function getHinges(): ?string
    {
        return $this->hinges;
    }

    public function setHinges(?string $hinges): self
    {
        $this->hinges = $hinges;

        return $this;
    }

    public function getWebcam(): ?string
    {
        return $this->webcam;
    }

    public function setWebcam(?string $webcam): self
    {
        $this->webcam = $webcam;

        return $this;
    }

    public function getSpeakers(): ?string
    {
        return $this->speakers;
    }

    public function setSpeakers(?string $speakers): self
    {
        $this->speakers = $speakers;

        return $this;
    }

    public function getDcIn(): ?string
    {
        return $this->dcIn;
    }

    public function setDcIn(?string $dcIn): self
    {
        $this->dcIn = $dcIn;

        return $this;
    }

    public function getCablekit(): ?string
    {
        return $this->cablekit;
    }

    public function setCablekit(?string $cablekit): self
    {
        $this->cablekit = $cablekit;

        return $this;
    }

    public function getDvddrive(): ?string
    {
        return $this->dvddrive;
    }

    public function setDvddrive(?string $dvddrive): self
    {
        $this->dvddrive = $dvddrive;

        return $this;
    }

    public function getUsbAudioboard(): ?string
    {
        return $this->usbAudioboard;
    }

    public function setUsbAudioboard(?string $usbAudioboard): self
    {
        $this->usbAudioboard = $usbAudioboard;

        return $this;
    }

    public function getSystemIoBoard(): ?string
    {
        return $this->systemIoBoard;
    }

    public function setSystemIoBoard(?string $systemIoBoard): self
    {
        $this->systemIoBoard = $systemIoBoard;

        return $this;
    }

    public function getFanHeatsink(): ?string
    {
        return $this->fanHeatsink;
    }

    public function setFanHeatsink(?string $fanHeatsink): self
    {
        $this->fanHeatsink = $fanHeatsink;

        return $this;
    }

    public function getBottomDoor(): ?string
    {
        return $this->bottomDoor;
    }

    public function setBottomDoor(?string $bottomDoor): self
    {
        $this->bottomDoor = $bottomDoor;

        return $this;
    }

    public function getMisc(): ?string
    {
        return $this->misc;
    }

    public function setMisc(?string $misc): self
    {
        $this->misc = $misc;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getHostname(): ?string
    {
        return $this->hostname;
    }

    public function setHostname(?string $hostname): self
    {
        $this->hostname = $hostname;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getSerialnumber(): ?string
    {
        return $this->serialnumber;
    }

    public function setSerialnumber(string $serialnumber): self
    {
        $this->serialnumber = $serialnumber;

        return $this;
    }

    public function getMac1(): ?string
    {
        return $this->mac1;
    }

    public function setMac1(?string $mac1): self
    {
        $this->mac1 = $mac1;

        return $this;
    }

    public function getMac2(): ?string
    {
        return $this->mac2;
    }

    public function setMac2(string $mac2): self
    {
        $this->mac2 = $mac2;

        return $this;
    }

    public function getProductCode(): ?string
    {
        return $this->productCode;
    }

    public function setProductCode(?string $productCode): self
    {
        $this->productCode = $productCode;

        return $this;
    }

    public function getServicemodelId(): ?string
    {
        return $this->servicemodelId;
    }

    public function setServicemodelId(?string $servicemodelId): self
    {
        $this->servicemodelId = $servicemodelId;

        return $this;
    }

    public function getServicemodelOrder(): ?string
    {
        return $this->servicemodelOrder;
    }

    public function setServicemodelOrder(?string $servicemodelOrder): self
    {
        $this->servicemodelOrder = $servicemodelOrder;

        return $this;
    }

    public function getEndoflife(): ?string
    {
        return $this->endoflife;
    }

    public function setEndoflife(?string $endoflife): self
    {
        $this->endoflife = $endoflife;

        return $this;
    }

    public function getWarranty(): ?string
    {
        return $this->warranty;
    }

    public function setWarranty(?string $warranty): self
    {
        $this->warranty = $warranty;

        return $this;
    }

    public function getIntOrderId(): ?string
    {
        return $this->intOrderId;
    }

    public function setIntOrderId(string $intOrderId): self
    {
        $this->intOrderId = $intOrderId;

        return $this;
    }

    public function getExtOrderTime(): ?string
    {
        return $this->extOrderTime;
    }

    public function setExtOrderTime(?string $extOrderTime): self
    {
        $this->extOrderTime = $extOrderTime;

        return $this;
    }

    public function getExtDeliveryTime(): ?string
    {
        return $this->extDeliveryTime;
    }

    public function setExtDeliveryTime(?string $extDeliveryTime): self
    {
        $this->extDeliveryTime = $extDeliveryTime;

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(?bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getFreefieldtag01(): ?string
    {
        return $this->freefieldtag01;
    }

    public function setFreefieldtag01(string $freefieldtag01): self
    {
        $this->freefieldtag01 = $freefieldtag01;

        return $this;
    }

    public function getFreefieldtag02(): ?string
    {
        return $this->freefieldtag02;
    }

    public function setFreefieldtag02(string $freefieldtag02): self
    {
        $this->freefieldtag02 = $freefieldtag02;

        return $this;
    }

    public function getFreefieldtag03(): ?string
    {
        return $this->freefieldtag03;
    }

    public function setFreefieldtag03(string $freefieldtag03): self
    {
        $this->freefieldtag03 = $freefieldtag03;

        return $this;
    }

    public function getFreefieldtag04(): ?string
    {
        return $this->freefieldtag04;
    }

    public function setFreefieldtag04(string $freefieldtag04): self
    {
        $this->freefieldtag04 = $freefieldtag04;

        return $this;
    }

    public function getFreefieldtag05(): ?string
    {
        return $this->freefieldtag05;
    }

    public function setFreefieldtag05(string $freefieldtag05): self
    {
        $this->freefieldtag05 = $freefieldtag05;

        return $this;
    }

	public function getSchoolId(): ?InstitutionId
	{
		return $this->schoolId;
	}

	public function setSchoolId(?InstitutionId $schoolId): self
	{
		$this->schoolId = $schoolId;

		return $this;
	}
}
