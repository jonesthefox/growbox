class AIRRELAY:
    def __init__(self, option: str) -> None:
        self.file = option

    def read(self) -> tuple[int]:
        with open(self.file, 'r') as file:
            data = int(file.read())

        result = (data,)
        return result
