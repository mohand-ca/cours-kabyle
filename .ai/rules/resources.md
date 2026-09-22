---
paths:
  - 'resources/**'
---

# Resources

## Always use translation keys — no hardcoded labels
Every user-facing string in Blade views and Livewire components must go through the translation system using `__('key')` or `@lang('key')`. Never write a raw French (or English) label directly in a view or component. Add translation keys to `lang/fr/` (and `lang/en/` for bilingual support) from the very first string. Flash messages, validation error messages added via `addError()`, and strings returned from component methods are the only acceptable exceptions — those should also be keyed where practical.
