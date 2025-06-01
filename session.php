<?php
// session.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function verifier_session() {
    if (!isset($_SESSION["connecte"]) || $_SESSION["connecte"] !== "1") {
        // Si l'utilisateur n'est pas connecté, on le redirige vers la page de login
        header("Location: login.php");
        exit;
    }
}

// Fonction pour récupérer le username de l'utilisateur connecté
function get_username() {
    return $_SESSION["username"] ?? null;
}

// Fonction pour récupérer le rôle de l'utilisateur connecté
function get_role() {
    return $_SESSION["role"] ?? null;
}
function is_connected() {
    return isset($_SESSION["connecte"]) && $_SESSION["connecte"] === "1";
}
// ✅ Fonction pour récupérer l'ID de l'utilisateur connecté
function get_user_id() {
    return $_SESSION["id_user"] ?? null;
}
?>
