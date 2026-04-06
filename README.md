<center><h1>Gestionnaire de Tickets - Projet 1ère Année de Bachelor</h1></center>

<h3>Ce projet propose une solution de création de tickets, de son traitment, et de son suivi.</h3>
Nécessite un compte composé d'un <code>login</code> et d'un <code>mot de passe</code>.
<img width="1920" height="1080" alt="Page de connexion" src="https://github.com/user-attachments/assets/3dac4fb3-8f8d-4a3d-b955-6c8eab8c3b95" />
Un compte ne peut qu'être créé par un <code>Administrateur</code> ou supérieur.
<hr>
<h5>Plusieurs grades avec différentes fonctionnalités :</h5>
<p><i>Tout compte, peut importe de son rôle, peut mondifier son mot de passe.</i>
<br><img width="1920" height="1080" alt="Modifier Profil Utilisateur" src="https://github.com/user-attachments/assets/824af338-84a3-449c-bb21-3c96f566d25c" /></p>
<ul>
  <li><u>Utilisateur :</u>
    <img width="1920" height="1080" alt="Accueil Utilisateur" src="https://github.com/user-attachments/assets/32a054ae-0873-4e5f-851e-fbc175675f08" /></li>
  <ul>
    <li>Créer un ticket</li>
    <li>Voir ses tickets dans leurs détails</li>
    <li>Supprimer un de ses ticket</li>
    <img width="1920" height="1080" alt="Mes Tickets Utilisateur" src="https://github.com/user-attachments/assets/ec72207d-a51a-47e4-a702-7ff6f4376db9" />
  </ul>
  <li><u>Technicien :</u>
  <img width="1920" height="1080" alt="Accueil Traitement Technicien" src="https://github.com/user-attachments/assets/37c45b5b-2441-4097-8715-67ae4dafb0cb" /></li>
  <ul>
    <li>Voir la liste des tickets</li>
    <li>Mettre à jour leurs états</li>
    <img width="1920" height="1080" alt="Liste Tickets" src="https://github.com/user-attachments/assets/a1051efe-ef9b-4c1a-8e0b-0930516477d3" />
  </ul>
  <li><u>Administrateur :</u>
  <img width="1920" height="1080" alt="Accueil Traitement Admin" src="https://github.com/user-attachments/assets/55958f9e-1a52-4cf3-80f5-94f6b44f8fee" /></li>
  <ul>
    <li>Voir la liste des tickets</li>
    <li>Mettre à jour leurs états</li>
    <li>Vision sur la liste de tout les comptes <code>utilisateur</code> et <code>technicien</code>
    <img width="1920" height="1080" alt="Gestion des utilisateurs Admin" src="https://github.com/user-attachments/assets/473f7c2a-c74a-4e64-83d5-0643b8f5d812" /></li>
    <li>Modifier un compte
    <img width="1920" height="1080" alt="Modifier utilisateur Admin" src="https://github.com/user-attachments/assets/cea67d0c-2aa8-4770-978f-6452a22aa2c7" /></li>
    <li>Créer un compte
    <img width="1920" height="1080" alt="Créer un nouvel utilisateur Admin" src="https://github.com/user-attachments/assets/d977067c-d6c7-4f79-94ab-c876b478dd76" /></li>
  </ul>
  <li><u>Super Administrateur :</u>
    <img width="1920" height="1080" alt="Accueil Traitement Super Admin" src="https://github.com/user-attachments/assets/c105eda9-2985-4b3b-9c8a-411562ce27fe" /></li>
  <ul>
    <li>Voir la liste des tickets</li>
    <li>Mettre à jour leurs états</li>
    <li>Vision sur la liste de tout les comptes utilisateur et technicien</li>
    <li>Modifier un compte</li>
    <li>Créer un compte</li>
    <li>Accéder à une console communiquante avec la base de données
    <img width="1920" height="1080" alt="Console SQL Super Admin" src="https://github.com/user-attachments/assets/ad70f680-0a86-485a-891b-f44cd3e7c2fd" /></li><br>
    <i>Exemple de requête :<br><img width="1920" height="1080" alt="Exemple SELECT Console SQL Super Admin" src="https://github.com/user-attachments/assets/167ddd18-4b9a-401e-a426-6b839ebd9cb7" /></i>
  </ul>
</ul>
<hr>
<center><h4>Synopsis</h4></center>
<h5>Base de données</h5>
<p>La premère étape a était de créer la base de données. Je me suis d'abord servi de <u>Looping</u> pour réalisé le <strong>MCD</strong>.</p>
<i>Schéma :</i>
<img width="1416" height="601" alt="Modèle Entité-Association" src="https://github.com/user-attachments/assets/3a230972-2adc-4236-a3df-9247eee43084" />
<img width="1416" height="632" alt="UML" src="https://github.com/user-attachments/assets/5ae1f097-ffc5-4551-8710-c5ce0c29462e" />
<img width="1416" height="630" alt="MLD" src="https://github.com/user-attachments/assets/77a87a88-765b-401a-9d39-d207bb3eb697" />
<p>(Vous trouverez le <strong>dictionnaire de données</strong> en PDF dans les documents mis à disposition.)</p>
<p>Une fois fini, j'ai pû exporter le modèle en <strong>Script LLD</strong> pour créer la base de données dans <u>phpMyAdmin</u>. Pour appliquer un serveur local, je me suis servi de <u>XAMPP</u>, me permétant d'appliquer <u>Apache</u> et <u>MySQL</u> en local.</p>
<hr>
<h5>Les pages WEB</h5>
<p>Une fois la base de données complettement fini, il était tant de se mettre au site.
J'ai utilisé <u>Visual Studio Code</u> pour coder les pages WEB. Je me suis servi de <strong>PHP</strong>, pour la communication entre l'utilisateur et la base de données, couplé au <strong>HTML</strong> et avec un léger <strong>CSS</strong>. Tout n'est pas parfait, mais c'est fonctionnel.</p>
<p>L'architecture du code PHP est composé de condition et d'évennement. Je me sert aussi de sa technologie de <code>SESSION</code> pour stocker les informations de l'utilisateur lors de sa visite, facilitant aussi le transfère d'informations pour naviguer entre les pages autorisés ou non en fonction du compte.</p>
<p>L'<strong>HTML</strong> est composé notamment en <code>div</code> pour découper les infomations en robriques à l'écran.</p>
<strong><p>Tout le code est commenter, vous pouvez vous baladez dans les fichier de code pour le comprendre.</p></strong>
<hr>
<h4>À vous de testez</h4>
<p><u>Tout les fichiers sont disponibles dans ce répertoir.</u> Pour tester le projet, vous pouvez télécharger tout les fichiers <code>.php</code> en gardant la même arborescence que présenté dans ce répertoir Github.</p>
<p>Une fois téléchargé, vous vouvez vous munir du code de créations des tables et éléments pour la base de données (<code>gestionnaire_de_tickets.sql</code>). <strong>⚠️N'oubliez pas de modifiez le nom et le chemin de connexion à la base de données dans les fichiers <code>.php</code> en fonction de l'endroit où se ttrouve la base de données.⚠️</strong> Si vous voulez tout tester en local, je vous propose de vous servir, comme je l'ai fait, de <strong>XAMPP</strong>. (Pour vous évitez une recherche : https://youtu.be/3mP3wiz6vN4?si=doJCX2YJj6jb3dkF (N'ésiter pas à chercher par vous même si le tuto ne répond pas à vos questions.))</p>
<h5>Login et mdp des utilisateur Test :</h5>
| Rôle | Login | Mot de passe |<br>
| Utilisateur | utilisateur | utilisateur |<br>
| Technicien | technicien | technicien |<br>
| Administrateur | admin | admin |<br>
| Super Administrateur | super-admin | super-admin |<br>
<hr>
<p>Retrouvez aussi ce projet en ligne mit en fonctionnement sur https://lagthe.free.nf/Gestionnaire_de_Tickets/, (les utilisateurs de test n'existent évidemment pas sur la version en ligne).</p>
