<?php

namespace App\Utils;

use App\Entity\Institution;
use App\Entity\InstitutionLocation;

class DashboardCounter {


    public function getInventoryForInstitution(Institution $institution) {
        $ret = 0;
        
        foreach($institution->getInstitutionLocations() as $location) {
            $ret += count($location->getDevices());
        }

        return $ret;
    }

    public function getInventoryForInstitutionLocation(InstitutionLocation $location) {
        return count($location->getDevices());
    }

    public function getClientsForInstitution(Institution $institution) {
        $ret = 0;

        foreach($institution->getInstitutionLocations() as $location) {
            $ret += count($location->getCustomers());
        }

        return $ret;
    }

    public function getClientsForInstitutionLocation(InstitutionLocation $location) {
        return count($location->getCustomers());
    }

    public function getClientsWithoutDevicesForInstitution(Institution $institution) {
        $ret = 0;
        
        foreach($institution->getInstitutionLocations() as $location) {
            foreach($location->getCustomers() as $customer) {
                if(count($customer->getDevices()) === 0 ) {
                    $ret++;
                }
            }
        }

        return $ret;
    }

    public function getClientsWithoutDevicesForInstitutionLocation(InstitutionLocation $location) {
        $ret = 0;

        foreach($location->getCustomers() as $customer) {
            if(count($customer->getDevices()) === 0 ) {
                $ret++;
            }
        }

        return $ret;
    }
}