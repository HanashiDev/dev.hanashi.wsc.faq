<?php

namespace wcf\system\attachment;

use Override;
use wcf\system\WCF;

final class FaqQuestionAttachmentObjectType extends AbstractAttachmentObjectType
{
    #[Override]
    public function canDownload($objectID)
    {
        return true;
    }

    #[Override]
    public function canUpload($objectID, $parentObjectID = 0)
    {
        return WCF::getSession()->getPermission('admin.faq.canAddQuestion');
    }

    #[Override]
    public function canDelete($objectID)
    {
        return WCF::getSession()->getPermission('admin.faq.canDeleteQuestion');
    }
}
