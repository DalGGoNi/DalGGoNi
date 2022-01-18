<?php
session_start();
session_destroy();
echo "<script>location.href='javascript:history.back()'</script>";
?>
