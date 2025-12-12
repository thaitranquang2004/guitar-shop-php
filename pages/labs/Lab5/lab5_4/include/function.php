<?php
function postIndex($index, $value="")
{
	if (!isset($_POST[$index]))	return $value;
	return $_POST[$index];
}

function getIndex($index, $value="")
{
	if (!isset($_GET[$index]))	return $value;
	return $_GET[$index];
}

function requestIndex($index, $value="")
{
	if (!isset($_REQUEST[$index]))	return $value;
	return $_REQUEST[$index];
}