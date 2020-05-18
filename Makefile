.PHONY: up
SHELL=/bin/bash -O extglob -O dotglob -c
SUDO=sudo
PROJECT_TEMP_DIR=project_tmp
TEMP_DIR=module_tmp
MKDIR=mkdir
MV=mv
RM=rm
CP=cp
CAT=cat
GIT=git
PROJECT_FRAMEWORK_REPO=git@bitbucket.org:orbainternalprojects/skeleton.git
EDITION=community
VERSION=2.3.4-p2
up:
	$(MKDIR) $(TEMP_DIR)
	$(MV) -t $(TEMP_DIR)/ !($(TEMP_DIR))
	$(GIT) clone $(PROJECT_FRAMEWORK_REPO) $(PROJECT_TEMP_DIR)
	$(RM) -rf ./$(PROJECT_TEMP_DIR)/.git
	$(MV) -t . ./$(PROJECT_TEMP_DIR)/*
	$(RM) -d $(PROJECT_TEMP_DIR)
	$(MAKE) new project=orba version=$(VERSION) edition=$(EDITION) ca=0
	$(CAT) ./$(TEMP_DIR)/make/Makefile.append >> Makefile
	$(CP) -r ./$(TEMP_DIR)/make/.idea ./.idea
	$(SUDO) $(MAKE) hosts
	$(MAKE) up
