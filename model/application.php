<?php
class Application
{
    private ?int $id;
    private int $id_offre;
    private string $status;
    private string $date_postulation;
    private string $cv;
    private string $full_name;
    private string $email;
    private string $phone;

    public function __construct(
        int $id_offre,
        string $status,
        string $cv,
        string $full_name,
        string $email,
        string $phone,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->id_offre = $id_offre;
        $this->status = $status;
        $this->cv = $cv;
        $this->full_name = $full_name;
        $this->email = $email;
        $this->phone = $phone;
        $this->date_postulation = date("Y-m-d H:i:s");
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getIdOffre(): int
    {
        return $this->id_offre;
    }
    public function getStatus(): string
    {
        return $this->status;
    }
    public function getDatePostulation(): string
    {
        return $this->date_postulation;
    }
    public function getCv(): string
    {
        return $this->cv;
    }

    public function getFullName(): string
    {
        return $this->full_name;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPhone(): string
    {
        return $this->phone;
    }

    
    public function setId(?int $id): void
    {
        $this->id = $id;
    }
    public function setIdOffre(int $id_offre): void
    {
        $this->id_offre = $id_offre;
    }
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
    public function setCv(string $cv): void
    {
        $this->cv = $cv;
    }

    public function setFullName(string $full_name): void
    {
        $this->full_name = $full_name;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }
}
