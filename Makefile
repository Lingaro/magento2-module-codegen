.PHONY: up

SHELL=/bin/bash -O extglob -O dotglob -c
UNAME = $(shell uname -s)
SUDO=sudo
PROJECT_TEMP_DIR=project_tmp
TEMP_DIR=module_tmp
MKDIR=mkdir
MV=mv
RM=rm
CP=cp
CAT=cat
GIT=git
COMPOSER=composer
PROJECT_FRAMEWORK_REPO=git@bitbucket.org:orbainternalprojects/skeleton.git
EDITION=community
VERSION=2.4.1

mount ?=

up:
	$(MKDIR) $(TEMP_DIR)
ifeq ($(UNAME), Darwin)
	$(MV) *[^$(TEMP_DIR)]* $(TEMP_DIR)
else
	$(MV) -t $(TEMP_DIR)/ !($(TEMP_DIR))
endif
	$(GIT) clone $(PROJECT_FRAMEWORK_REPO) $(PROJECT_TEMP_DIR)
	$(RM) -rf ./$(PROJECT_TEMP_DIR)/.git
ifeq ($(UNAME), Darwin)
	$(MV) ./$(PROJECT_TEMP_DIR)/* .
else
	$(MV) -t . ./$(PROJECT_TEMP_DIR)/*
endif
	$(RM) -d $(PROJECT_TEMP_DIR)
	$(MAKE) new project=orba version=$(VERSION) edition=$(EDITION) ca=0
	$(CAT) ./$(TEMP_DIR)/make/Makefile.append >> Makefile
	$(CP) -rf ./$(TEMP_DIR)/make/source/magento ./source
	$(MKDIR) -p source/magento/lib/internal/codegen
	$(MV) ./module_tmp/* ./source/magento/lib/internal/codegen
	$(RM) -d module_tmp
	$(SUDO) $(MAKE) hosts
	$(MAKE) init
ifeq (,$(mount))
	$(MAKE) install-package
	$(MAKE) up
else
	$(MAKE) install-package mount=$(mount)
	$(MAKE) up mount=$(mount)
endif
