<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UsuarioRepository::class)
 */
class Usuario implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    private $imagenFile;
    /**
     * @ORM\Column(type="string", length=255)
     */
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imagen;

    /**
     * @ORM\OneToMany(targetEntity=Juego::class, mappedBy="propietario")
     */
    private $juegos;

    /**
     * @ORM\OneToOne(targetEntity=Juego::class, mappedBy="comprador", cascade={"persist", "remove"})
     */
    private $juego_comprado;

    public function __construct()
    {
        $this->juegos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        echo '<script>';
        echo 'console.log('. json_encode( $password ) .')';
        echo '</script>';
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
    /**
     * @return mixed
     */
    public function getImagenFile()
    {
        return $this->imagenFile;
    }

    /**
     * @param mixed $imagenFile
     * @return Usuario
     */
    public function setImagenFile($imagenFile)
    {
        $this->imagenFile = $imagenFile;
        return $this;
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }
    public function getRoles()
    {
        return [$this->getRole()];
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->getNombre();
    }

    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->nombre,
            $this->password,
            $this->role
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->nombre,
            $this->password,
            $this->role
        ) = unserialize($serialized);
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(?string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * @return Collection|Juego[]
     */
    public function getJuegos(): Collection
    {
        return $this->juegos;
    }

    public function addJuego(Juego $juego): self
    {
        if (!$this->juegos->contains($juego)) {
            $this->juegos[] = $juego;
            $juego->setPropietario($this);
        }

        return $this;
    }

    public function removeJuego(Juego $juego): self
    {
        if ($this->juegos->removeElement($juego)) {
            // set the owning side to null (unless already changed)
            if ($juego->getPropietario() === $this) {
                $juego->setPropietario(null);
            }
        }

        return $this;
    }

    public function getJuegoComprado(): ?Juego
    {
        return $this->juego_comprado;
    }

    public function setJuegoComprado(?Juego $juego_comprado): self
    {
        // unset the owning side of the relation if necessary
        if ($juego_comprado === null && $this->juego_comprado !== null) {
            $this->juego_comprado->setComprador(null);
        }

        // set the owning side of the relation if necessary
        if ($juego_comprado !== null && $juego_comprado->getComprador() !== $this) {
            $juego_comprado->setComprador($this);
        }

        $this->juego_comprado = $juego_comprado;

        return $this;
    }
}
