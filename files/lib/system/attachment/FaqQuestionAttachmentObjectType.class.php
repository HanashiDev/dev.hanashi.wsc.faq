<?php

namespace wcf\system\attachment;

use wcf\system\WCF;

final class FaqQuestionAttachmentObjectType extends AbstractAttachmentObjectType
{
    /**
     * @inheritDoc
     */
    public function canDownload($objectID)
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function canUpload($objectID, $parentObjectID = 0)
    {
        return WCF::getSession()->getPermission('admin.faq.canAddQuestion');
    }

    /**
     * @inheritDoc
     */
    public function canDelete($objectID)
    {
        return WCF::getSession()->getPermission('admin.faq.canDeleteQuestion');
    }
}
