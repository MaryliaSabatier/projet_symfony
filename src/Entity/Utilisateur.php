<?php
// src/Entity/Utilisateur.php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: "Le nom d'utilisateur est obligatoire")]
    #[Assert\Length(min: 2, max: 180, minMessage: "Le nom d'utilisateur doit faire au moins {{ limit }} caractères", maxMessage: "Le nom d'utilisateur ne peut pas dépasser {{ limit }} caractères")]
    private ?string $nomUtilisateur = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le mot de passe est obligatoire")]
    private ?string $motDePasse = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le rôle est obligatoire")]
    #[Assert\Choice(choices: ['Administrateur', 'Moderateur', 'Utilisateur'], message: "Choisissez un rôle valide")]
    private ?string $role = null;

    #[ORM\OneToMany(mappedBy: 'auteur', targetEntity: Evenement::class, orphanRemoval: true)]
    private Collection $evenements;

    #[ORM\OneToMany(mappedBy: 'auteur', targetEntity: Post::class, orphanRemoval: true)]
    private Collection $posts;

    #[ORM\OneToMany(mappedBy: 'auteur', targetEntity: Commentaire::class, orphanRemoval: true)]
    private Collection $commentaires;

    #[ORM\ManyToMany(targetEntity: Discussion::class, inversedBy: 'utilisateurs')]
    private Collection $discussions;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Abonnement::class, orphanRemoval: true)]
    private Collection $abonnements;

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->discussions = new ArrayCollection();
        $this->abonnements = new ArrayCollection();
    }

    // ... (getters, setters, autres méthodes de l'interface UserInterface et PasswordAuthenticatedUserInterface)
}
