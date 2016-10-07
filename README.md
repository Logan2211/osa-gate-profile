Generate metrics from openstack-ansible gate check console logs.

- step1: fetchlogs.php
- step2: parselogs.php
- step3: generatereports.php

ie.
```bash
mkdir dump
php fetchlogs.php
php parselogs.php > intermediary.json
php generatereports.php intermediary.json
rm -rf intermediary.json
```
