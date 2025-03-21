<?php
function rupiah($nominal) {
    return "Rp ".number_format($nominal);
}

function dolar($nominal) {
    return "USD ".number_format($nominal);
}