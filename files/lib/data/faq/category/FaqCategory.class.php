<?php

namespace wcf\data\faq\category;

use wcf\data\category\AbstractDecoratedCategory;
use wcf\data\IAccessibleObject;
use wcf\data\ITitledLinkObject;
use wcf\data\user\User;
use wcf\data\user\UserProfile;
use wcf\system\category\CategoryPermissionHandler;
use wcf\system\WCF;

/**
 * @method      FaqCategory[]    getChildCategories()
 * @method      FaqCategory[]    getAllChildCategories()
 * @method      FaqCategory      getParentCategory()
 * @method      FaqCategory[]    getParentCategories()
 * @method static FaqCategory|null getCategory($categoryID)
 */
class FaqCategory extends AbstractDecoratedCategory implements IAccessibleObject, ITitledLinkObject
{
    public const OBJECT_TYPE_NAME = 'dev.tkirch.wsc.faq.category';

    protected $userPermissions = [];

    /**
     * @inheritDoc
     */
    public function isAccessible(?User $user = null)
    {
        if ($this->getObjectType()->objectType !== self::OBJECT_TYPE_NAME) {
            return false;
        }

        return $this->getPermission('canViewFAQ', $user);
    }

    public function getPermission($permission, ?User $user = null, $isMod = false)
    {
        if ($user === null) {
            $user = WCF::getUser();
        }

        if (!isset($this->userPermissions[$user->userID])) {
            $this->userPermissions[$user->userID] = CategoryPermissionHandler::getInstance()->getPermissions(
                $this->getDecoratedObject(),
                $user
            );
        }

        if (isset($this->userPermissions[$user->userID][$permission])) {
            return $this->userPermissions[$user->userID][$permission];
        }

        if ($this->getParentCategory()) {
            return $this->getParentCategory()->getPermission($permission, $user);
        }

        if ($user->userID === WCF::getSession()->getUser()->userID) {
            return WCF::getSession()->getPermission((($isMod) ? 'mod' : 'user') . '.faq.' . $permission);
        } else {
            $userProfile = new UserProfile($user);

            return $userProfile->getPermission((($isMod) ? 'mod' : 'user') . '.faq.' . $permission);
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return WCF::getLanguage()->get($this->title);
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @inheritDoc
     */
    public function getLink()
    {
        return null;
    }
}
