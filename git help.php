<?PHP
echo '
 1ere etape pc serveur
 modification du fichier
 git init
 git status

 A/configuration obligatoire 
 1/a configure en git config --local  user.name, user.email  colo.ui    mieux que globale 
 2/touch .gitignore  creer un fichier pour ignorer certain fichier leo des commites 
 Il faut donc tout réinitialiser. pour git ignor marche 
 git rm -r --cached .
 git add .
 git commit -m ".gitignore est maintenant fonctionnel"   commit = enregistrer les modifications
 //*****************************//
 
 
 
 touch tiba.php  creer un fichier 
 git add tiba.php
 git commit -m "modification"  ou git commit -a -m "modification"  pour aller plus rapidement sans passer par le add . ou --all
 git log --oneline
 git log -p fichie
 
 git checkout  4f2c294  permet de revenir en arriere  sans possibiliter de modification 
 
 
 
 git remote add origin https://github.com/tibaredha/mdo.git
 git push -u origin master
 username tibaredha@yahoo.fr   
 pasword  git030570
 2eme etape serveur pc
 modification dans le serveur 
 pour metre ajour le pc
 git pull origin master
 fin methode
 
 NB editeur de message 
 taper i pour insertion  message 
 How to exit the insert screen in mac terminal
 Press esc, then colon (:) and then enter on keyboard wq to save and exit
 If you wish to just quit, without saving, write q without w
 
 
 
 
 
 git branch  slave  pour creer une brache
 git checkout slave pour se mettre sur la branch 
 pour suprimer une brache  il fant utiliser  git branch -d  nom de la branch 
 git branch -d slave  or git branch -D slave  avec un grand d si avant  de merger 
 git branch master
 git merge slave  pour rapatrier les modification a master
 
 branches type 
 1master
 2develop
 
 3Feature branches  = fonctionalites
 4Release branches  =
 5Hotfix branches   = correctf branche
 
';
?>
