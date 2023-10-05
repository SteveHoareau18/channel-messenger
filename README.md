# Instructions lors de chaque pull

- Vérifier s'il y a de nouvelles dépendances PHP

```console
> composer install
```

- Vérifier s'il y a de nouvelles dépendances Javascript

```console
> npm install
```

- Mettre à jour les migrations

```console
> php bin\console doctrine:migrations:migrate
```