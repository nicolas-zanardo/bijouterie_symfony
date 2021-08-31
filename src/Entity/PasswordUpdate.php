<?php


namespace App\Entity;


use Symfony\Component\Validator\Constraints as Assert;

class PasswordUpdate
{
    /**
     * @Assert\NotBlank(message="Veuillez renseigner votre ancien mot de passe")
     */
    private $oldPassword;
    /**
     * @Assert\NotBlank(message="Veuillez renseigner votre mot de passe")
     * @Assert\EqualTo(
     *     propertyPath="confirmPassword",
     *     message="Les mots de passe ne sont pas identiques"
     * )
     */
    private $newPassword;
    /**
     * @Assert\NotBlank(message="Veuillez confirmer votre  mot de passe")
     */
    private $confirmPassword;

    /**
     * @return string | null
     */
    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }
    /**
     * @param string | null $oldPassword
     */
    public function setOldPassword(?string $oldPassword): void
    {
        $this->oldPassword = $oldPassword;
    }

    /**
     * @return string | null
     */
    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }
    /**
     * @param string | null $newPassword
     */
    public function setNewPassword(?string $newPassword): void
    {
        $this->newPassword = $newPassword;
    }

    /**
     * @return string | null
     */
    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }
    /**
     * @param string | null $confirmPassword
     */
    public function setConfirmPassword(?string $confirmPassword): void
    {
        $this->confirmPassword = $confirmPassword;
    }


}