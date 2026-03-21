<?php
function upgrade_module_4_0_0()
{
    Tools::clearSf2Cache();
    return true;
}