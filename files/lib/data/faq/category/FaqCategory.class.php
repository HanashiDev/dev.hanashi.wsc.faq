<?php

namespace wcf\data\faq\category;

use Override;
use wcf\data\category\AbstractDecoratedCategory;
use wcf\data\IAccessibleObject;
use wcf\data\ITitledLinkObject;
use wcf\data\user\User;
use wcf\data\user\UserProfile;
use wcf\page\FaqQuestionListPage;
use wcf\system\category\CategoryPermissionHandler;
use wcf\system\request\LinkHandler;
use wcf\system\style\FontAwesomeIcon;
use wcf\system\WCF;

/**
 * @method      FaqCategory[]    getChildCategories()
 * @method      FaqCategory[]    getAllChildCategories()
 * @method      FaqCategory      getParentCategory()
 * @method      FaqCategory[]    getParentCategories()
 * @method static FaqCategory|null getCategory($categoryID)
 */
final class FaqCategory extends AbstractDecoratedCategory implements IAccessibleObject, ITitledLinkObject
{
    public const OBJECT_TYPE_NAME = 'dev.tkirch.wsc.faq.category';

    protected array $userPermissions = [];

    private bool $prefix = false;

    #[Override]
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
        }

        return (new UserProfile($user))->getPermission((($isMod) ? 'mod' : 'user') . '.faq.' . $permission);
    }

    #[Override]
    public function getTitle(): string
    {
        return ($this->prefix ? '&nbsp;&nbsp;' : '') . WCF::getLanguage()->get($this->title);
    }

    public function setPrefix($prefix = true)
    {
        $this->prefix = $prefix;
    }

    #[Override]
    public function getLink(): string
    {
        return LinkHandler::getInstance()->getControllerLink(FaqQuestionListPage::class, [
            'object' => $this->getDecoratedObject(),
        ]);
    }

    public function getIcon(int $size = 24): string
    {
        if (
            isset($this->additionalData['faqIcon'])
            && FontAwesomeIcon::isValidString($this->additionalData['faqIcon'])
        ) {
            return FontAwesomeIcon::fromString($this->additionalData['faqIcon'])->toHtml($size);
        }

        return '';
    }
}
