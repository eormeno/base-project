```mermaid
---
title: Diagrama de clases
config:
    class:
        hideEmptyMembersBox: true
---
classDiagram
    direction TD
    class User {
    }
    class GameApp {
        +string prefix
        +string name
        +string description
        +int min_age
        +string image
        +string client
        +int width
        +int height
        +string version
        +int max_instances_per_user
        +int min_users_per_instance
        +int max_users_per_instance
        +bool active
        +json service_registry
    }
    class Game {
        +string invitation_code
    }
    class GameService {
        <<abstract>>
    }
    class game_user {
        <<pivot>>
    }
    class Prefab {
        +string name
        +string type
        +json structure
        +GameObject instantiate()
    }
    class GameObject {
        +string name
        +bool active
        +GameObject parent
        +GameObject[] children
        +request(event)
        +view()
    }
    class Component {
        +string type
        +bool enabled
        +bool awoke
        +static state()
        +onAwake()
        +onStart()
        +onUpdate(delta)
    }
    class StateViewComponent {
        #string state
        #string view_name
        +view()
    }
    Game --> "1" GameObject : game_object
    Game "1" -- "0..n" GameService : has
    GameObject "1" -- "0..n" Component : components
    GameObject --> "0..1" Component : state_component
    GameApp "1" -- "0..n" Game : games
    Game "1" -- "1..n" game_user : players
    GameApp "0..n" -- "1" Prefab : prefab
    game_user "0..n" -- "1" User : games
    StateViewComponent --|> Component
```

#### Diagrama de StateViewComponent
```mermaid
classDiagram
    direction LR
    class IState {
        <<interface>>
        +static state()
        +handleStateEvent(event)
        +onEnter()
        +onExit()
        +passTo()
    }
    class IView {
        <<interface>>
        +view()
    }
    class StateViewComponent {
        #string state
        #string view_name
        +view()
    }
    StateViewComponent ..|> IState
    StateViewComponent ..|> IView
```

#### Sistema de mensajes
El sistema de mensajes consta de un servicio que busca en tiempo de ejecución un proveedor de mensajes para la clase actual.
```mermaid
classDiagram
    direction LR
    class IMessageProvider {
        <<interface>>
        +getMessages(parameters)
    }
    class MessageService {
        +getMessages(parameters)
    }
    MessageService --> "n" IMessageProvider : providers
```

```mermaid
---
title: Diagrama de entidad relación
---
erDiagram
    game_apps ||--o{ games : "has"
    games ||--o{ game_user : "played by"
    game_objects ||--o{ components : "has"
    game_objects one or zero -- zero or many game_objects : "children"
    prefabs one or zero --o{ game_apps : ""
    users ||--o{ game_user : "plays"
    games one or zero -- 1 game_objects : "has"

    game_apps {
        int id PK
        string prefix
        string name
        string description
        int min_age
        string image
        string prefab_name FK
        string version
        int max_instances_per_user
        int min_users_per_instance
        int max_users_per_instance
        bool active
    }

    games {
        int id PK
        int game_app_id FK
        int game_object_id FK
        string invitation_code
    }

    game_user {
        int id PK
        int game_id FK
        int user_id FK
        datetime created_at
        datetime updated_at
    }

    prefabs {
        string name FK
        json structure
    }

    game_objects {
        int id PK
        int game_object_id FK
        int state_component_id FK
        string name
        bool active
    }

    components {
        int id PK
        int game_object_id FK
        string type
        string state
        bool enabled
        bool awoke
    }

    users {
        int id PK
        string name
        string email
    }
```

