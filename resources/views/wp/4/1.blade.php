<style>
  p {
      margin-bottom: 20px;
  }
  ul {
      list-style-type: disc;
      margin-left: 20px;
      margin-bottom: 20px;
  }
  ul li {
      margin-bottom: 10px;
  }
  ol {
      list-style-type: decimal;
      margin-left: 20px;
      margin-bottom: 20px;
  }
  ol li {
      margin-bottom: 10px;
  }
</style>

<div>
  <h2>Modified DPoS Consensus Mechanism</h2>
  <p>For the most part, consensus will run on the Tendermint BFT engine. However, it will be modified to implement a non-standard, upgraded DPoS (Delegated Proof of Stake) consensus mechanism.</p>

  <h3>Key Differences from Standard DPoS</h3>
  <ul>
      <li><strong>Delegator Pool (P1):</strong> In standard DPoS, delegators delegate their tokens directly to a chosen validator. In our modified DPoS, delegators delegate their funds to a general Pool of Delegators (P1). The total amount in this pool is considered as 100% for the block of delegators.</li>
      <li><strong>Validator Pool (P2):</strong> Validators form their own pool (P2), which determines their voting power. Validators are selected based on their contributions and performance within the network.</li>
      <li><strong>Voting Power Limitation:</strong> The project enforces a condition at both the blockchain and DAO levels that no participant can hold more than 8% of the total voting power.</li>
  </ul>

  <h3>DAO-Based Project Management</h3>
  <p>All project management is based on the principles of a DAO (Decentralized Autonomous Organization). The DAO is managed by active project participants who contribute to the project through various actions, such as:</p>
  <ul>
      <li>Programming and development</li>
      <li>Participating in hackathons</li>
      <li>Copywriting and content creation</li>
      <li>Translations and localization</li>
      <li>Public relations (PR) and marketing</li>
      <li>Proposing ideas for project improvements</li>
  </ul>
  <p>These participants are rewarded based on their contributions, ensuring that the project benefits from a diverse range of skills and expertise.</p>
</div>