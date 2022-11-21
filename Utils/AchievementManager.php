<?php

namespace Utils;

use Entities\AchievementEntity;
use Entities\UserEntity;
use Exceptions\DataNotFoundException;
use Repositories\AchievementRepository;

class AchievementManager
{
    private UserEntity $user;

    public function __construct(UserEntity $user)
    {
        $this->user = $user;
    }

    //function to check if the user has the achievement
    public function hasAchievement(int $achievementId): bool
    {
        try {
            $achievements = AchievementRepository::getAchievementsByUser($this->user);
        } catch (DataNotFoundException $e) {
            return false;
        }
        foreach ($achievements as $achievement) {
            if ($achievement->getId() == $achievementId) {
                return true;
            }
        }
        return false;
    }

    //function to check that the user has the achievement and if not, add it
    public function checkAchievement(AchievementEntity $achievementEntity): void
    {
        if (!$this->hasAchievement($achievementEntity->getId())) {
            AchievementRepository::awardAchievement($this->user, $achievementEntity);
        }
    }

    //function that checks requirements for the achievement and if they are met, adds it




}