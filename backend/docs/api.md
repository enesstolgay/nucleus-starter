# Auth API

Both endpoints below are handled by Symfony **security authenticators**, not
controllers. They live in the firewall layer (`config/packages/security.yaml`)
and never reach routing/controller resolution on success.

To inspect this yourself:
- `bin/console debug:router` — list all routes
- `bin/console debug:firewall <name>` — which authenticator handles a firewall
- `bin/console config:dump-reference <BundleName>` — a bundle's config options
- `bin/console debug:container <keyword>` — a bundle's registered services

## POST /api/login_check

Handled by `Symfony\Component\Security\Http\Authenticator\JsonLoginAuthenticator`
(firewall `login`, config in `security.yaml`).

Request:
```json
{
  "email": "admin@nucleus.dev",
  "password": "admin123"
}
```

Response `200`:
```json
{
  "token": "<jwt access token>",
  "refresh_token": "<refresh token>"
}
```

`refresh_token` is attached by
`Gesdinet\JWTRefreshTokenBundle\EventListener\AttachRefreshTokenOnSuccessLoginListener`
on top of the lexik JWT success handler.

Response `401` on bad credentials:
```json
{ "code": 401, "message": "Invalid credentials." }
```

Access token TTL: 900s (15 min), set in `config/packages/lexik_jwt_authentication.yaml`.

## POST /api/token/refresh

Handled by `Gesdinet\JWTRefreshTokenBundle\Security\Http\Authenticator\RefreshTokenAuthenticator`
(firewall `refresh`, config in `security.yaml` under `refresh-jwt:`).

Request:
```json
{
  "refresh_token": "<refresh token from login_check>"
}
```

Response `200`:
```json
{
  "token": "<new jwt access token>",
  "refresh_token": "<refresh token, same value — single_use is false>"
}
```

Refresh token TTL: 2592000s (30 days), set in
`config/packages/gesdinet_jwt_refresh_token.yaml`. `ttl_update: true` means
every refresh extends the token's validity by another 30 days.

## Using the access token

Send it as a bearer token on any other `/api/*` route:
```
Authorization: Bearer <token>
```
Handled by the `api` firewall (`jwt: ~`, lexik bundle).
