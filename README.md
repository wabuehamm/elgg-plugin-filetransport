# Elgg Filetransport Plugin

## Introduction

This plugin sets the default notification method to Zend\Mail\Transport\File and writes all mail to elgg-data/notifications_log/zend. It can be used to reroute mails in development or integration environments to files instead of sending them out.

## Installation

Download the zip in the elgg plugin and place it in your Elgg's mod folder. Activate the plugin in the plugins administration and be sure to place it to the bottom of the plugin list.

## Configuration

Use the plugin configuration to change the path to where the mails are stored.