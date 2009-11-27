DROP TABLE IF EXISTS workflow;
CREATE TABLE workflow (
  workflow_id                INTEGER      UNSIGNED NOT NULL AUTO_INCREMENT,
  workflow_name              VARCHAR(32)           NOT NULL,
  workflow_version           INTEGER      UNSIGNED NOT NULL DEFAULT 1,
  workflow_created           INTEGER               NOT NULL,

  PRIMARY KEY              (workflow_id),
  UNIQUE  KEY name_version (workflow_name, workflow_version)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS node;
CREATE TABLE node (
  workflow_id        INTEGER      UNSIGNED NOT NULL REFERENCES workflow.workflow_id,
  node_id            INTEGER      UNSIGNED NOT NULL AUTO_INCREMENT,
  node_class         VARCHAR(255)          NOT NULL,
  node_configuration TEXT                  NOT NULL,

  PRIMARY KEY             (node_id),
          KEY workflow_id (workflow_id)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS node_connection;
CREATE TABLE node_connection (
  incoming_node_id INTEGER UNSIGNED NOT NULL,
  outgoing_node_id INTEGER UNSIGNED NOT NULL,

  KEY incoming_node_id (incoming_node_id)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS variable_handler;
CREATE TABLE variable_handler (
  workflow_id   INTEGER      UNSIGNED NOT NULL REFERENCES workflow.workflow_id,
  variable      VARCHAR(255)          NOT NULL,
  class         VARCHAR(255)          NOT NULL,

  PRIMARY KEY (workflow_id, class)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS execution;
CREATE TABLE execution (
  workflow_id              INTEGER UNSIGNED NOT NULL REFERENCES workflow.workflow_id,
  execution_id             INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  execution_parent         INTEGER UNSIGNED NOT NULL REFERENCES execution.execution_id,
  execution_started        INTEGER          NOT NULL,
  execution_variables      TEXT             NOT NULL,
  execution_waiting_for    TEXT             NOT NULL,
  execution_threads        TEXT             NOT NULL,
  execution_next_thread_id INTEGER UNSIGNED NOT NULL,

  PRIMARY KEY                  (execution_id, workflow_id),
          KEY execution_parent (execution_parent)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS execution_state;
CREATE TABLE execution_state (
  execution_id          INTEGER UNSIGNED NOT NULL REFERENCES execution.execution_id,
  node_id               INTEGER UNSIGNED NOT NULL REFERENCES node.node_id,
  node_state            TEXT             NOT NULL,
  node_activated_from   TEXT             NOT NULL,
  node_thread_id        INTEGER UNSIGNED NOT NULL,

  PRIMARY KEY (execution_id, node_id)
) ENGINE=InnoDB;
