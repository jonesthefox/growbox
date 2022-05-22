class SUN:
    def __init__(self, option: str) -> None:
        self.file = option

    def read(self) -> tuple[float]:
        import json
        with open(self.file, 'r') as json_file:
            data = json.load(json_file)
            return float(data['brightness']),
