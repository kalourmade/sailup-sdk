# Sailup SDKs

Client SDKs for the [Sailup](https://www.sailup.io) SMS gateway.

| Language | Package | Registry | Docs |
|---|---|---|---|
| PHP | `kalourmade/sailup-sms` | [Packagist](https://packagist.org/packages/kalourmade/sailup-sms) | [docs/php.md](docs/php.md) |
| Python | `kalourmade-sailup` | [PyPI](https://pypi.org/project/kalourmade-sailup/) | [docs/python.md](docs/python.md) |
| Node | `@kalourmade/sailup-sms` | [npm](https://www.npmjs.com/package/@kalourmade/sailup-sms) | [docs/node.md](docs/node.md) |

Each SDK covers: sending SMS, listing/fetching messages, and verifying
delivery webhooks. See the per-language guide for install + quickstart.

## Status

v1 covers the SMS API only. Contacts and Lists APIs are planned as
additive follow-ups (see `docs/superpowers/specs/` for the roadmap).

## Releasing

All three packages are released together from a single tag — there is
no such thing as releasing one without the others. Pushing a tag like
`v0.1.2` triggers `.github/workflows/release.yml`, which reads the
version from the tag itself and publishes all three packages at that
version in one run:

```bash
git tag v0.1.2
git push origin v0.1.2
```

`package.json` and `pyproject.toml` don't carry a hand-maintained
version number for this reason — CI overwrites it from the tag before
building. `composer.json` never needs one; Packagist derives PHP
package versions from git tags directly.
