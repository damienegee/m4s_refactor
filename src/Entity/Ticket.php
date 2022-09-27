<?php

namespace App\Entity;

use DateTime;

class Ticket extends Serialize
{
	private $id;
	private $created_at;
	private $problem;
	private $description;
	private $state;
	private $school;
	private $schoollocation;
	private $device;
	private $customer;
	private $modified_at;

	public function getId(): ?int
	{
		return $this->id;
	}
	public function setId(int $id): self
	{
		$this->id = $id;
		return $this;
	}

	public function getCreatedAt(): ?DateTime
	{
		return $this->created_at;
	}
	public function setCreatedAt(DateTime $created_at): self
	{
		$this->created_at = $created_at;
		return $this;
	}

	public function getProblem(): ?string
	{
		return $this->problem;
	}
	public function setProblem(string $problem): self
	{
		$this->problem = $problem;
		return $this;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}
	public function setDescription(string $description): self
	{
		$this->description = $description;
		return $this;
	}

	public function getState(): ?string
	{
		return $this->state;
	}
	public function setState(string $state): self
	{
		$this->state = $state;
		return $this;
	}

	public function getSchool(): ?int
	{
		return $this->school;
	}
	public function setSchool(int $school): self
	{
		$this->school = $school;
		return $this;
	}

	public function getSchoollocation(): ?int
	{
		return $this->schoollocation;
	}
	public function setSchoollocation(int $schoollocation): self
	{
		$this->schoollocation = $schoollocation;
		return $this;
	}

	public function getDevice(): ?int
	{
		return $this->device;
	}
	public function setDevice(int $device): self
	{
		$this->device = $device;
		return $this;
	}

	public function getCustomer(): ?int
	{
		return $this->customer;
	}
	public function setCustomer(int $customer = null): self
	{
		$this->customer = $customer;
		return $this;
	}

	public function getModifiedAt(): ?DateTime
	{
		return $this->modified_at;
	}
	public function setModifiedAt(DateTime $modified_at): self
	{
		$this->modified_at = $modified_at;
		return $this;
	}
}
