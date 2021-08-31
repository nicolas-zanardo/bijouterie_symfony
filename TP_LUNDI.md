TP DE LUNDI
-----------


- Création d'une class concernant les images upload (gérer nom (length ?), envoie dans le dossier imagesUpload )


- CRUD Catégorie


- route profil (sécurité => security.yaml)
- route profil/modifier
- route password/modifier (entity sans orm   3 property ==> holdpassword newpassword confirmnewpassword)
- suppression du compte

formulaire userType
-> option inscription
-> option profil (tout sauf mdp)


twig
app.user.nom


controller
$this->getUser();
