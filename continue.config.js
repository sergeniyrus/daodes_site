/** @type {import("continue").Config} */
const config = {
  models: [
    {
      title: "DeepSeek-Coder (Ollama)",
      provider: "ollama",                     // ← Обязательно "ollama"
      model: "deepseek-coder:6.7b-instruct-q5_K_M"
    }
  ],
  allowAnonymousTelemetry: false
};

export default config;