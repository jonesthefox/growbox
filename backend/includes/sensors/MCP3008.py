class MCP3008:
    def __init__(self, option: str, bus: int = 0, device: int = 0) -> None:
        from spidev import SpiDev
        self.channel = int(option)
        self.bus, self.device = bus, device
        self.spi = SpiDev()
        self.open()
        self.spi.max_speed_hz = 1000000  # 1MHz

    def open(self) -> None:
        self.spi.open(self.bus, self.device)
        self.spi.max_speed_hz = 1000000  # 1MHz

    def read(self) -> int:
        cmd1 = 4 | 2 | ((self.channel & 4) >> 2)
        cmd2 = (self.channel & 3) << 6

        adc = self.spi.xfer2([cmd1, cmd2, 0])
        data = ((adc[1] & 15) << 8) + adc[2]
        return data

    def close(self):
        self.spi.close()

    def __del__(self):
        self.close()