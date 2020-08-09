<?php

namespace App\Entity;

use App\Repository\MesurementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MesurementRepository::class)
 */
class Mesurement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="float")
     */
    private $temperature;

    /**
     * @ORM\Column(type="float")
     */
    private $windSpeed;

    /**
     * @ORM\Column(type="integer")
     */
    private $windDirection;

    /**
     * @ORM\Column(type="float")
     */
    private $pressure;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return mb_convert_case($this->city, MB_CASE_TITLE, "UTF-8");
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(?float $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getWindSpeed(): ?float
    {
        return $this->windSpeed;
    }

    public function setWindSpeed(?float $windSpeed): self
    {
        $this->windSpeed = $windSpeed;

        return $this;
    }

    public function getWindDirection(): ?int
    {
        return $this->windDirection;
    }

    public function setWindDirection(?int $windDirection): self
    {
        $this->windDirection = $windDirection;

        return $this;
    }

    public function getPressure(): ?float
    {
        return $this->pressure;
    }

    public function setPressure(?float $pressure): self
    {
        $this->pressure = $pressure;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(?\DateTimeInterface $timestamp = null): self
    {
        if (is_null($timestamp)) {
            $timestamp = new \DateTime('NOW');
        }

        $this->timestamp = $timestamp;

        return $this;
    }

    public function fromRowResponse($response, $city): self
    {
        return $this
            ->setCity($city)
            ->setTemperature($response['temperatura'])
            ->setWindSpeed($response['predkosc_wiatru'])
            ->setWindDirection($response['kierunek_wiatru'])
            ->setPressure($response['cisnienie'])
            ->setTimestamp();
    }
}
