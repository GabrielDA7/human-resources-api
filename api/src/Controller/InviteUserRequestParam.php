<?php


namespace App\Controller;


class InviteUserRequestParam
{
    private int $userEmail;
    private int $offerId;

    /**
     * @return int
     */
    public function getUserEmail(): int
    {
        return $this->userEmail;
    }

    /**
     * @param int $userEmail
     */
    public function setUserEmail(int $userEmail): void
    {
        $this->userEmail = $userEmail;
    }

    /**
     * @return int
     */
    public function getOfferId(): int
    {
        return $this->offerId;
    }

    /**
     * @param int $offerId
     */
    public function setOfferId(int $offerId): void
    {
        $this->offerId = $offerId;
    }



}
