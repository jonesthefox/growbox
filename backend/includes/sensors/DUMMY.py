import random


class CPUTEMP:
    def __init__(self, option: str) -> None:
        self.option = option

    @staticmethod
    def read() -> tuple[float]:
        return float(random.randrange(0, 100)),
