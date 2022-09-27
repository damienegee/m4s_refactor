<?php

namespace App\Enum;

class CustomerType extends EnumType {
    
    const EMPLOYEE = "EMPLOYEE";
    const ADMINISTRATION = "ADMINISTRATION";
    const SALES = "SALES";
    const STUDENT = "STUDENT";
    const TEACHER = "TEACHER";
    const ICT_COORDINATOR = "ICT COORDINATOR";

    protected $name = self::class;

    protected $values = array(
        self::EMPLOYEE => self::EMPLOYEE,
        self::ADMINISTRATION => self::ADMINISTRATION,
        self::SALES => self::SALES,
        self::STUDENT => self::STUDENT,
        self::TEACHER => self::TEACHER,
        self::ICT_COORDINATOR => self::ICT_COORDINATOR
    );
}