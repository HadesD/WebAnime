<?php

function studly_case_domain($domain)
{
  return studly_case((preg_replace('/[^a-z0-9]/', '_', strtolower($domain))));
}