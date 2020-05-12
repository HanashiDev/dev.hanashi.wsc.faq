@ECHO OFF
set PATH=%PATH%;C:\Program Files\7-Zip\
del dev.tkirch.wsc.faq.tar
del files.tar
del templates.tar
del acptemplates.tar
7z a -ttar -mx=9 files.tar .\files\*
7z a -ttar -mx=9 acptemplates.tar .\acptemplates\*
7z a -ttar -mx=9 templates.tar .\templates\*
7z a -ttar -mx=9 dev.tkirch.wsc.faq.tar .\* -x!dev.tkirch.wsc.faq.tar -x!files -x!files_wcf -x!templates -x!acptemplates -x!make.bat -x!packages -x!README.md -x!.git -x!.gitignore -x!make.sh
del files.tar
del templates.tar
del acptemplates.tar
