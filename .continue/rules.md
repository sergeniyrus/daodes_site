# DAODES Project Rules

## Code Style
- PHP 8.2+ with strict typing
- PSR-12 coding standards
- Laravel 11 conventions
- All methods must have PHPDoc

## AI Components
- Keep ai/ directory organized
- Update living_graph.json on every change
- Validate JSON syntax
- Follow existing patterns

## Git Workflow
- Feature branches for new features
- Descriptive commit messages
- No direct commits to main
- Code review required

## Testing
- Write tests for critical AI components
- Maintain test coverage > 80%
- Test both unit and integration

## Deployment
- Check living_graph.json integrity
- Clear cache after changes
- Run migrations if needed
- Update documentation