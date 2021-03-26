# Esis Salama - Horaire Bot

Ce bot Telegram permet de récupérer l'horaire de cours sur le site d'Esis Salama rapidement et simplement en rentrant la
promotion, le bot envoie l'horaire sous format PDF

La prochaine version de ce bot enverra directement l'horaire aux étudiants abonnés au canal de distribution, et pourra
enregistrer en tant qu’évènements sur google calendrier ou apple calendrier pour obtenir des rappels journalier même
hors connexion

# Installation

```bash
git clone https://github.com/devscast/esis-horaire-bot esis-bot
cd esis-bot

composer install
```

# Mise à jour de l'horaire

L'horaire de cours est mis à jour chaque semaine, pour cela le bot doit obtenir les nouvelles informations afin de les
distribuées en temps réels, pour cela il faudra créer une tâche CRON pour chaque dimanche soir en appelant la commande
suivant

CRON : **0 0 * * SUN**

```bash
php bin/console bot:fetch-timetable
```

La commande suivant mettra à jour, l'horaire disponible pour le bot afin de délivrer les informations mise à jour

# Activer le Bot Telegram

Pour activer le bot telegram il faudra le créer grâce au bot father, obtenir votre token et la definir dans les
variables d'environnement du serveur dans le fichier ```.env.local``` qui doit être une copie du fichier ```.env```

```dotenv
TELEGRAM_BOT_TOKEN=votre-token
```

En suite pour obtenir les commandes des étudiants il faudra définir un webhook afin que le bot répondent vous pouvez
soit utilisez les variables d'environnement

```dotenv
TELEGRAM_WEBHOOK_URL=https://votre-serveur.com
```

Et tapez la commande suivant sans précisez l'URL dans le cas contraire rajouter l'URL qui va supporter le webhook de
Telegram

```bash
php bin/console bot:telegram-webhook [url]
```

# Copyright

Créateur et Mainteneur : [bernard-ng](https://github.com/bernard-ng)

Plus d'information dans la LICENSE
