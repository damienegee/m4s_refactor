<?php

namespace App\Entity;

class Block extends Serialize
{
	private $id;

	private $webshop_id;

	private $title;

	private $content;

	private $school_logo;

	private $course_label;

	private $created;

	private $modified;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getWebshopId(): ?string
	{
		return $this->webshop_id;
	}

	public function setWebshopId(string $webshopId): self
	{
		$this->webshop_id = $webshopId;

		return $this;
	}

	public function getTitle(): ?string
	{
		return $this->title;
	}

	public function setTitle(string $title): self
	{
		$this->title = $title;

		return $this;
	}

	public function getContent(): ?string
	{
		return $this->content;
	}

	public function setContent(string $content): self
	{
		$this->content = $content;

		return $this;
	}

	public function getSchoolLogo(): ?string
	{
		return $this->school_logo;
	}

	public function setSchoolLogo(string $schoolLogo): self
	{
		$this->school_logo = $schoolLogo;

		return $this;
	}

	public function getCourseLabel(): ?string
	{
		return $this->course_label;
	}

	public function setCourseLabel(string $courseLabel): self
	{
		$this->course_label = $courseLabel;

		return $this;
	}

	public function getCreated(): ?\DateTimeInterface
	{
		return $this->created;
	}

	public function setCreated(\DateTimeInterface $created): self
	{
		$this->created = $created;

		return $this;
	}

	public function getModified(): ?\DateTimeInterface
	{
		return $this->modified;
	}

	public function setModified(\DateTimeInterface $modified): self
	{
		$this->modified = $modified;

		return $this;
	}

}
