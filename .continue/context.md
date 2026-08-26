# DAODES AI System Context

## Project Overview
- **Name**: DAODES AI Engine
- **Type**: Laravel 11 + AI System
- **Location**: /var/www/daodes

## Directory Structure
/var/www/daodes/
├── ai/
│ ├── architecture/ # Архитектура AI системы
│ ├── engine/ # Ядро AI движка
│ ├── graph/ # Граф знаний
│ ├── rules/ # Правила поведения
│ └── continue_context.md
├── app/
│ ├── Http/
│ │ └── Controllers/ # Laravel контроллеры
│ └── Models/ # Laravel модели
└── routes/
├── api.php # API маршруты
└── web.php # Web маршруты


## Core Components
1. **AI Engine**: Обработка запросов и генерация ответов
2. **Knowledge Graph**: Хранит связи между сущностями
3. **Rules System**: Определяет поведение AI
4. **Living Graph**: Актуальное состояние системы

## Integration Points
- Laravel 11 для веб-интерфейса
- Ollama для LLM
- Векторная база для эмбеддингов
