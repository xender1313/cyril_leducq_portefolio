<?php
// Inclure l'autoloader de Composer si disponible
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser les données du formulaire
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validation des champs
    if (empty($nom) || empty($email) || empty($message)) {
        echo "Tous les champs sont requis.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Adresse email invalide.";
        exit;
    }

    // Initialiser l'API SendGrid avec la clé API récupérée depuis les variables d'environnement
    $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));

    // Préparer le contenu de l'email
    $email_content = "Nom: $nom\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Message:\n$message\n";

    // Créer l'email avec SendGrid
    $mail = new \SendGrid\Mail\Mail();
    $mail->setFrom("noreply@votre-domaine.com", "Formulaire de Contact");
    $mail->setSubject("Nouveau message de $nom via le formulaire de contact");
    $mail->addTo("cyril.leducq.pro@gmail.com", "Cyril Leducq");
    $mail->addContent("text/plain", $email_content);

    // Envoyer l'email via SendGrid
    try {
        $response = $sendgrid->send($mail);
        if ($response->statusCode() == 202) {
            // Redirection vers la page de remerciement
            header("Location: merci.html");
            exit();
        } else {
            echo "Une erreur s'est produite lors de l'envoi de votre message.";
        }
    } catch (Exception $e) {
        echo 'Exception capturée: ' . $e->getMessage() . "\n";
    }
} else {
    echo "Méthode de requête non valide.";
}
?>
