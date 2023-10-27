<?php $name = $request->query->get("name",'stranger'); ?>

Hello <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>
