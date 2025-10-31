// continue.config.js
/** @type {import("continue").Config} */
const config = {
  models: [
  {
    title: "Llama3 8B (Laravel)",
    provider: "ollama",
    model: "llama3:8b-instruct-q5_K_M",
    contextLength: 4096, // ↓ уменьшил с 8192 → меньше памяти и быстрее
    parameters: {
      temperature: 0.3,   // ↓ меньше = более детерминированный и быстрый
      top_p: 0.9,
      repeat_penalty: 1.1,
      num_predict: 512,   // ↓ макс. длина ответа — не даём "писать эссе"
      num_ctx: 2048       // ↓ контекст только для текущей задачи
    }
  }
],
  allowAnonymousTelemetry: false,
  systemMessage: `Ты — эксперт по Laravel 10/11 и PHP 8.2+. 
Пиши чистый, идиоматичный код: используй Eloquent, контроллеры, 
Form Requests, API Resources, миграции и best practices фреймворка. 
Не объясняй очевидное — просто дай рабочий код.`
};

export default config;