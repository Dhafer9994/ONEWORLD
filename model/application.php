<?php
class Application {
    private ?int $id;
    private int $id_offre;
    private string $status;
    private string $date_postulation;
    private string $cv;

    public function __construct(
        int $id_offre,
        string $status,
        string $cv,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->id_offre = $id_offre;
        $this->status = $status;
        $this->cv = $cv;
        $this->date_postulation = date("Y-m-d H:i:s");
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getIdOffre(): int { return $this->id_offre; }
    public function getStatus(): string { return $this->status; }
    public function getDatePostulation(): string { return $this->date_postulation; }
    public function getCv(): string { return $this->cv; }

    // Setters
    public function setId(?int $id): void { $this->id = $id; }
    public function setIdOffre(int $id_offre): void { $this->id_offre = $id_offre; }
    public function setStatus(string $status): void { $this->status = $status; }
    public function setCv(string $cv): void { $this->cv = $cv; }
}
