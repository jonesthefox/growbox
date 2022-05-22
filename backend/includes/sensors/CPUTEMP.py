class CPUTEMP:
    def __init__(self, option: str) -> None:
        self.sysfs_tz = option

    def read(self) -> tuple[float]:
        with open(self.sysfs_tz, 'r') as thermalfile:
            cputemp = int(thermalfile.read()) / 1000  # 27.27ms

        result = (round(cputemp, 2),)

        return result
