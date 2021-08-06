#!/bin/bash
rm -rf dev.tkirch.wsc.faq.tar
rm -rf files.tar
rm -rf templates.tar
rm -rf acptemplates.tar
7z a -ttar -mx=9 files.tar ./files/*
7z a -ttar -mx=9 acptemplates.tar ./acptemplates/*
7z a -ttar -mx=9 templates.tar ./templates/*
7z a -ttar -mx=9 dev.tkirch.wsc.faq.tar ./* -x!dev.tkirch.wsc.faq.tar -x!files -x!files_wcf -x!templates -x!acptemplates -x!make.bat -x!packages -x!README.md -x!.git -x!.gitignore -x!.travis.yml
rm -rf files.tar
rm -rf templates.tar
rm -rf acptemplates.tar
